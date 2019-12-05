package io.tronalddump.app.home

import geb.Page
import io.tronalddump.app.Url

class HomePage extends Page {
    static url = Url.INDEX

    static content = {
        searchForm() {
            $("form[action='${Url.SEARCH_QUOTE}']")
        }
        searchInputField() { searchForm.find('input[name="query"]') }
        searchSubmitButton() { searchForm.find('input[type="submit"]') }
    }

    static at = {
        title.startsWith 'Tronald Dump'
        searchForm.isDisplayed()
    }

    def typeInSearchInputField(query){
        searchInputField.value(query)
    }

    def clickOnSearchSubmitButton(){
        searchSubmitButton.click()
    }
}
