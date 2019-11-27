package io.tronalddump.app.quote

import io.tronalddump.app.Url
import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.hateoas.MediaTypes.HAL_JSON_VALUE
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*

@RequestMapping(value = [Url.QUOTE])
@RestController
class QuoteController(
        private val assembler: QuoteModelAssembler,
        private val repository: QuoteRepository
) {

    @ResponseBody
    @RequestMapping(
            headers = [
                "${HttpHeaders.ACCEPT}=${MediaType.APPLICATION_JSON_VALUE}",
                "${HttpHeaders.ACCEPT}=$HAL_JSON_VALUE"
            ],
            method = [RequestMethod.GET],
            produces = [MediaType.APPLICATION_JSON_VALUE],
            value = ["/{id}"]
    )
    fun findById(
            @RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String,
            @PathVariable id: String
    ): QuoteModel {
        val entity = repository.findById(id).orElseThrow {
            EntityNotFoundException("Quote with id \"$id\" not found.")
        }

        return assembler.toModel(entity)
    }
}
