package io.tronalddump.app.quote_source

import io.swagger.v3.oas.annotations.Operation
import io.swagger.v3.oas.annotations.media.Content
import io.swagger.v3.oas.annotations.media.Schema
import io.swagger.v3.oas.annotations.responses.ApiResponse
import io.swagger.v3.oas.annotations.responses.ApiResponses
import io.swagger.v3.oas.annotations.tags.Tag
import io.tronalddump.app.Url
import io.tronalddump.app.exception.EntityNotFoundException
import io.tronalddump.app.quote.QuoteModel
import org.springframework.hateoas.MediaTypes.HAL_JSON_VALUE
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*

@RequestMapping(value = [Url.QUOTE_SOURCE])
@RestController
@Tag(name = "quote-source", description = "Service to retrieve and create quotes sources")
class QuoteSourceController(
        private val assembler: QuoteSourceModelAssembler,
        private val repository: QuoteSourceRepository
) {

    @ApiResponses(value = [
        ApiResponse(responseCode = "200", content = [
            Content(schema = Schema(implementation = QuoteSourceModel::class))
        ])
    ])
    @Operation(summary = "Find an quotes by its id", tags = ["quote-source"])
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
    ): QuoteSourceModel {
        val entity = repository.findById(id).orElseThrow {
            EntityNotFoundException("QuoteSource with id \"$id\" not found.")
        }

        return assembler.toModel(entity)
    }
}