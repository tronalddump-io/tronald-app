package io.tronalddump.app.search

import geb.Page
import io.tronalddump.app.Url

class SearchPage extends Page {
    static url = Url.SEARCH_QUOTE

    static content = {
        searchForm() { $("form[action='${Url.SEARCH_QUOTE}']") }
        searchResult() { $('#search-result') }
        searchInputField() { searchForm.find('input[name="query"]') }
        searchSubmitButton() { searchForm.find('input[type="submit"]') }
    }

    static at = {
        title.startsWith 'Search for:'
        searchForm.isDisplayed()
        searchResult.isDisplayed()
    }

    def total() {
        return searchResult
                .find('p > small')
                .text()
                .find(/\d+/)
                .toInteger()
    }
}
