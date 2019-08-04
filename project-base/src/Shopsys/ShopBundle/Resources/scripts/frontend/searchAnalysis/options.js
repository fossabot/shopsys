(function ($) {

    Shopsys = window.Shopsys || {};
    Shopsys.searchAnalysis = Shopsys.searchAnalysis || {};

    searchAnalysisOptions = window.searchAnalysisOptions || {};

    var defaults = {
        collectQueryUrl: null,
        searchInputSelector: '#js-search-autocomplete-input'
    };

    Shopsys.searchAnalysis.options = $.extend({}, defaults, searchAnalysisOptions);

})(jQuery);
