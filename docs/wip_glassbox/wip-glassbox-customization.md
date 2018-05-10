# WIP Glassbox Customization

## Entities
* visibility of all private properties and methods of DataFactories was changed to protected (@Miroslav-Stopka)
    * protected visibility allows overriding of behavior from projects
* visibility of all private properties and methods of repositories of entities was changed to protected (@Miroslav-Stopka)
    * there are changed only repositories of entities because currently there was no need for extendibility of other repositories
    * protected visibility allows overriding of behavior from projects

## Forms
* adding a new field into form in administration is now enabled via form type extending, see [prototype](https://github.com/shopsys/shopsys/commit/d6b84bf54c0b47c72eacc82d540987dd8078fa13).
* extensible [shopsys/framework package](https://github.com/shopsys/framework) forms are:
    * `VatSettingsFormType`
    * `SliderItemFormType`
    * `ShopInfoSettingFormType`
    * `SeoSettingFormType`
    * `MailSettingFormType`
    * `LegalConditionsSettingFormType`
    * `HeurekaShopCertificationFormType`
    * `CustomerCommunicationFormType`
    * `CookiesSettingFormType`
    * `CategoryFormType`
    * `ArticleFormType`
    * `AdvertFormType`
    * `AdministratorFormType`

## Migrations
* migrations now can be installed from all bundles registered in application, directory should be in bundle_root/Migrations folder

## Entities
* entities can be extended by inheritance
* all entities are extensible via `%shopsys.entity_extension.map%` parameter
* more info in separate article [Entity Extension](entity-extension.md)
* reasons and alternatives to this approach are explained in [Entity Extension vs. Entity Generation](entity-extension-vs-entity-generation.md)
