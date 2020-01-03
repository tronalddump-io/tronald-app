package io.tronalddump.app.quote

import io.swagger.v3.oas.annotations.Operation
import io.swagger.v3.oas.annotations.media.Content
import io.swagger.v3.oas.annotations.media.Schema
import io.swagger.v3.oas.annotations.responses.ApiResponse
import io.swagger.v3.oas.annotations.responses.ApiResponses
import io.swagger.v3.oas.annotations.tags.Tag
import io.tronalddump.app.Url
import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.hateoas.MediaTypes.HAL_JSON_VALUE
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*
import org.springframework.web.servlet.ModelAndView

@RequestMapping(value = [Url.QUOTE])
@RestController
@Tag(name = "quote", description = "Service to retrieve and create quotes")
class QuoteController(
        private val assembler: QuoteModelAssembler,
        private val repository: QuoteRepository
) {

    @ApiResponses(value = [
        ApiResponse(responseCode = "200", content = [
            Content(schema = Schema(implementation = QuoteModel::class))
        ])
    ])
    @Operation(summary = "Find a quote by its id", tags = ["quote"])
    @ResponseBody
    @RequestMapping(
            headers = [
                "${HttpHeaders.ACCEPT}=$HAL_JSON_VALUE",
                "${HttpHeaders.ACCEPT}=${MediaType.APPLICATION_JSON_VALUE}",
                "${HttpHeaders.ACCEPT}=${MediaType.TEXT_HTML_VALUE}"
            ],
            method = [RequestMethod.GET],
            produces = [MediaType.APPLICATION_JSON_VALUE, MediaType.TEXT_HTML_VALUE],
            value = ["/{id}"]
    )
    fun findById(
            @RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String,
            @PathVariable id: String
    ): Any {
        val entity = repository.findById(id).orElseThrow {
            EntityNotFoundException("Quote with id \"$id\" not found.")
        }

        if (acceptHeader.contains(MediaType.TEXT_HTML_VALUE)) {
            return ModelAndView("quote")
                    .addObject("quote", entity)
        }

        return assembler.toModel(entity)
    }
}
