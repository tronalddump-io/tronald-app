package io.tronalddump.app.random

import io.swagger.v3.oas.annotations.Operation
import io.swagger.v3.oas.annotations.media.Content
import io.swagger.v3.oas.annotations.media.Schema
import io.swagger.v3.oas.annotations.responses.ApiResponse
import io.swagger.v3.oas.annotations.responses.ApiResponses
import io.tronalddump.app.Url
import io.tronalddump.app.meme.MemeGenerator
import io.tronalddump.app.quote.QuoteModel
import io.tronalddump.app.quote.QuoteModelAssembler
import io.tronalddump.app.quote.QuoteRepository
import io.tronalddump.app.quote_source.QuoteSourceModel
import io.tronalddump.app.search.PageModel
import org.springframework.hateoas.MediaTypes
import org.springframework.http.HttpHeaders
import org.springframework.http.HttpStatus
import org.springframework.http.MediaType
import org.springframework.http.ResponseEntity
import org.springframework.web.bind.annotation.*
import org.springframework.web.servlet.mvc.method.annotation.StreamingResponseBody

@RequestMapping(value = [Url.RANDOM])
@RestController
class RandomController(
        private val assembler: QuoteModelAssembler,
        private val memeGenerator: MemeGenerator,
        private val repository: QuoteRepository
) {

    @ApiResponses(value = [
        ApiResponse(responseCode = "200", content = [
            Content(schema = Schema(implementation = ResponseEntity::class))
        ])
    ])
    @Operation(summary = "Retrieve a random quote meme", tags = ["quote"])
    @ResponseBody
    @RequestMapping(
            headers = [
                "${HttpHeaders.ACCEPT}=${MediaType.IMAGE_JPEG_VALUE}"
            ],
            method = [RequestMethod.GET],
            value = ["/meme"]
    )
    fun meme(@RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String): ResponseEntity<StreamingResponseBody> {
        var quote = repository.randomQuote().get()

        val headers = HttpHeaders()
        headers.contentType = MediaType.IMAGE_JPEG
        headers.set("X-Quote-Id", quote.quoteId)

        val stream = StreamingResponseBody {
            memeGenerator.generate(it, quote)
        }

        return ResponseEntity(
                stream,
                headers,
                HttpStatus.OK
        )
    }

    @ApiResponses(value = [
        ApiResponse(responseCode = "200", content = [
            Content(schema = Schema(implementation = QuoteModel::class))
        ])
    ])
    @Operation(summary = "Retrieve a random quote", tags = ["quote"])
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
    fun random(@RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String): QuoteModel {
        return assembler.toModel(
                repository.randomQuote().get()
        )
    }
}
