package io.tronalddump.app.random

import io.tronalddump.app.Url
import io.tronalddump.app.quote.QuoteModel
import io.tronalddump.app.quote.QuoteModelAssembler
import io.tronalddump.app.quote.QuoteRepository
import org.springframework.hateoas.MediaTypes
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*

@RequestMapping(value = [Url.RANDOM])
@RestController
class RandomController(
        private val assembler: QuoteModelAssembler,
        private val repository: QuoteRepository
) {

    @ResponseBody
    @RequestMapping(
            headers = [
                "${HttpHeaders.ACCEPT}=${MediaType.APPLICATION_JSON_VALUE}",
                "${HttpHeaders.ACCEPT}=${MediaTypes.HAL_JSON_VALUE}"
            ],
            method = [RequestMethod.GET],
            produces = [MediaType.APPLICATION_JSON_VALUE],
            value = ["/quote"]
    )
    fun random(
            @RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String
    ): QuoteModel {
        return assembler.toModel(
                repository.randomQuote().get()
        )
    }
}
