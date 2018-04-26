<?php

namespace Shopsys\FrameworkBundle\Model\Transport;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Image\ImageFacade;
use Shopsys\FrameworkBundle\Model\Payment\PaymentRepository;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;

class TransportFacade
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Payment\PaymentRepository
     */
    protected $paymentRepository;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Transport\TransportRepository
     */
    protected $transportRepository;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Transport\TransportVisibilityCalculation
     */
    protected $transportVisibilityCalculation;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Image\ImageFacade
     */
    protected $imageFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade
     */
    protected $currencyFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Transport\TransportPriceCalculation
     */
    protected $transportPriceCalculation;

    public function __construct(
        EntityManagerInterface $em,
        TransportRepository $transportRepository,
        PaymentRepository $paymentRepository,
        TransportVisibilityCalculation $transportVisibilityCalculation,
        Domain $domain,
        ImageFacade $imageFacade,
        CurrencyFacade $currencyFacade,
        TransportPriceCalculation $transportPriceCalculation
    ) {
        $this->em = $em;
        $this->transportRepository = $transportRepository;
        $this->paymentRepository = $paymentRepository;
        $this->transportVisibilityCalculation = $transportVisibilityCalculation;
        $this->domain = $domain;
        $this->imageFacade = $imageFacade;
        $this->currencyFacade = $currencyFacade;
        $this->transportPriceCalculation = $transportPriceCalculation;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\TransportEditData $transportEditData
     * @return \Shopsys\FrameworkBundle\Model\Transport\Transport
     */
    public function create(TransportEditData $transportEditData)
    {
        $transport = new Transport($transportEditData->transportData);
        $this->em->persist($transport);
        $this->em->flush();
        $this->updateTransportPrices($transport, $transportEditData->pricesByCurrencyId);
        $this->createTransportDomains($transport, $transportEditData->transportData->domains);
        $this->imageFacade->uploadImage($transport, $transportEditData->transportData->image->uploadedFiles, null);
        $this->em->flush();

        return $transport;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\Transport $transport
     * @param \Shopsys\FrameworkBundle\Model\Transport\TransportEditData $transportEditData
     */
    public function edit(Transport $transport, TransportEditData $transportEditData)
    {
        $transport->edit($transportEditData->transportData);

        $this->updateTransportPrices($transport, $transportEditData->pricesByCurrencyId);
        $this->deleteTransportDomainsByTransport($transport);
        $this->createTransportDomains($transport, $transportEditData->transportData->domains);
        $this->imageFacade->uploadImage($transport, $transportEditData->transportData->image->uploadedFiles, null);
        $this->em->flush();
    }

    /**
     * @param int $id
     * @return \Shopsys\FrameworkBundle\Model\Transport\Transport
     */
    public function getById($id)
    {
        return $this->transportRepository->getById($id);
    }

    /**
     * @param int $id
     */
    public function deleteById($id)
    {
        $transport = $this->getById($id);
        $transport->markAsDeleted();
        $paymentsByTransport = $this->paymentRepository->getAllByTransport($transport);
        foreach ($paymentsByTransport as $payment) {
            $payment->getTransports()->removeElement($transport);
        }
        $this->deleteTransportDomainsByTransport($transport);
        $this->em->flush();
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\Transport $transport
     * @param array $domainIds
     */
    protected function createTransportDomains(Transport $transport, array $domainIds)
    {
        foreach ($domainIds as $domainId) {
            $transportDomain = new TransportDomain($transport, $domainId);
            $this->em->persist($transportDomain);
        }
        $this->em->flush();
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\Transport $transport
     */
    protected function deleteTransportDomainsByTransport(Transport $transport)
    {
        $transportDomains = $this->getTransportDomainsByTransport($transport);
        foreach ($transportDomains as $transportDomain) {
            $this->em->remove($transportDomain);
        }
        $this->em->flush();
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Payment\Payment[] $visiblePayments
     * @return \Shopsys\FrameworkBundle\Model\Transport\Transport[]
     */
    public function getVisibleOnCurrentDomain(array $visiblePayments)
    {
        return $this->getVisibleByDomainId($this->domain->getId(), $visiblePayments);
    }

    /**
     * @param int $domainId
     * @param \Shopsys\FrameworkBundle\Model\Payment\Payment[] $visiblePaymentsOnDomain
     * @return \Shopsys\FrameworkBundle\Model\Transport\Transport[]
     */
    public function getVisibleByDomainId($domainId, $visiblePaymentsOnDomain)
    {
        $transports = $this->transportRepository->getAllByDomainId($domainId);

        return $this->transportVisibilityCalculation->filterVisible($transports, $visiblePaymentsOnDomain, $domainId);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\Transport $transport
     * @return \Shopsys\FrameworkBundle\Model\Transport\TransportDomain[]
     */
    public function getTransportDomainsByTransport(Transport $transport)
    {
        return $this->transportRepository->getTransportDomainsByTransport($transport);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\Transport $transport
     * @param string[] $pricesByCurrencyId
     */
    protected function updateTransportPrices(Transport $transport, $pricesByCurrencyId)
    {
        foreach ($this->currencyFacade->getAll() as $currency) {
            $price = $pricesByCurrencyId[$currency->getId()];
            $transport->setPrice($currency, $price);
        }
    }

    public function getAll()
    {
        return $this->transportRepository->getAll();
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Transport\Transport[]
     */
    public function getAllIncludingDeleted()
    {
        return $this->transportRepository->getAllIncludingDeleted();
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency $currency
     * @return string[]
     */
    public function getTransportPricesWithVatIndexedByTransportId(Currency $currency)
    {
        $transportPricesWithVatByTransportId = [];
        $transports = $this->getAllIncludingDeleted();
        foreach ($transports as $transport) {
            $transportPrice = $this->transportPriceCalculation->calculateIndependentPrice($transport, $currency);
            $transportPricesWithVatByTransportId[$transport->getId()] = $transportPrice->getPriceWithVat();
        }

        return $transportPricesWithVatByTransportId;
    }

    /**
     * @return string[]
     */
    public function getTransportVatPercentsIndexedByTransportId()
    {
        $transportVatPercentsByTransportId = [];
        $transports = $this->getAllIncludingDeleted();
        foreach ($transports as $transport) {
            $transportVatPercentsByTransportId[$transport->getId()] = $transport->getVat()->getPercent();
        }

        return $transportVatPercentsByTransportId;
    }
}
