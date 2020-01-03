package io.tronalddump.app.author

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

@RequestMapping(value = [Url.AUTHOR])
@RestController
@Tag(name = "author", description = "Service to retrieve and create authors")
class AuthorController(
        private val assembler: AuthorModelAssembler,
        private val repository: AuthorRepository
) {

    @ApiResponses(value = [
        ApiResponse(responseCode = "200", content = [
            Content(schema = Schema(implementation = AuthorModel::class))
        ])
    ])
    @Operation(summary = "Find an author by its id", tags = ["author"])
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
    ): AuthorModel {
        val entity = repository.findById(id).orElseThrow {
            EntityNotFoundException("Author with id \"$id\" not found.")
        }

        return assembler.toModel(entity)
    }
}