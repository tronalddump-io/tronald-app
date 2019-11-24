package io.tronalddump.app.quote

import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*

@RequestMapping(value = ["/quote"])
@RestController
class QuoteController(
        private val repository: QuoteRepository
) {

    /**
     * Returns a Quote [QuoteEntity] by id.
     *
     * @param id The quote id
     * @return quote
     */
    @ResponseBody
    @RequestMapping(
            headers = [HttpHeaders.ACCEPT + "=" + MediaType.APPLICATION_JSON_VALUE],
            method = [RequestMethod.GET],
            produces = [MediaType.APPLICATION_JSON_VALUE],
            value = ["/{id}"]
    )
    fun findById(@PathVariable id: String): QuoteEntity {
        val quoteEntity = repository.findById(id).orElseThrow {
            EntityNotFoundException("Quote with id \"$id\" not found.")
        }

        return quoteEntity
    }
}