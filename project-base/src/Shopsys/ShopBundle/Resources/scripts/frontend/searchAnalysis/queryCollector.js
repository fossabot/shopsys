(function ($) {

    Shopsys = window.Shopsys || {};
    Shopsys.searchAnalysis = Shopsys.searchAnalysis || {};
    Shopsys.searchAnalysis.instances = Shopsys.searchAnalysis.instances || {};

    $(document).ready(function () {
        var $searchInput = $(Shopsys.searchAnalysis.options.searchInputSelector);
        var collectQueryUrl = Shopsys.searchAnalysis.options.collectQueryUrl;

        if (Shopsys.searchAnalysis.options.collectQueryUrl !== null && $searchInput.length > 0) {
            Shopsys.searchAnalysis.instances.queryCollector = new Shopsys.searchAnalysis.QueryCollector(
                collectQueryUrl,
                $searchInput
            );
            Shopsys.searchAnalysis.instances.queryCollector.init();
        }
    });

    Shopsys.searchAnalysis.QueryCollector = function (collectQueryUrl, $searchInput) {
        var self = this;

        self.init = function () {
            $searchInput.bind('change', self.collect);
        };

        self.collect = function () {
            Shopsys.ajax({
                url: collectQueryUrl,
                method: 'post',
                data: {
                    q: $searchInput.val()
                }
            });
        };
    };

})(jQuery);
