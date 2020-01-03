package io.tronalddump.app.tag

import io.swagger.v3.oas.annotations.Operation
import io.swagger.v3.oas.annotations.media.Content
import io.swagger.v3.oas.annotations.media.Schema
import io.swagger.v3.oas.annotations.responses.ApiResponse
import io.swagger.v3.oas.annotations.responses.ApiResponses
import io.swagger.v3.oas.annotations.tags.Tag
import io.tronalddump.app.Url
import io.tronalddump.app.exception.EntityNotFoundException
import io.tronalddump.app.quote.QuoteModel
import io.tronalddump.app.search.PageModel
import org.springframework.hateoas.MediaTypes.HAL_JSON_VALUE
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*
import org.springframework.web.servlet.ModelAndView

@RequestMapping(value = [Url.TAG])
@RestController
@Tag(name = "tag", description = "Service to retrieve and create tags")
class TagController(
        private val assembler: TagModelAssembler,
        private val repository: TagRepository
) {

    @ApiResponses(value = [
        ApiResponse(responseCode = "200", content = [
            Content(schema = Schema(implementation = PageModel::class))
        ])
    ])
    @Operation(summary = "Retrieve all tags", tags = ["tag"])
    @ResponseBody
    @RequestMapping(
            headers = [
                "${HttpHeaders.ACCEPT}=${MediaType.APPLICATION_JSON_VALUE}",
                "${HttpHeaders.ACCEPT}=$HAL_JSON_VALUE"
            ],
            method = [RequestMethod.GET],
            produces = [MediaType.APPLICATION_JSON_VALUE]
    )
    fun findAll(
            @RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String
    ): PageModel {
        val result: List<TagEntity> = repository.findAll()

        return assembler.toPageModel(result)
    }

    @ApiResponses(value = [
        ApiResponse(responseCode = "200", content = [
            Content(schema = Schema(implementation = TagModel::class))
        ])
    ])
    @Operation(summary = "Find a tag by its value", tags = ["tag"])
    @ResponseBody
    @RequestMapping(
            headers = [
                "${HttpHeaders.ACCEPT}=$HAL_JSON_VALUE",
                "${HttpHeaders.ACCEPT}=${MediaType.APPLICATION_JSON_VALUE}",
                "${HttpHeaders.ACCEPT}=${MediaType.TEXT_HTML_VALUE}"
            ],
            method = [RequestMethod.GET],
            produces = [
                HAL_JSON_VALUE,
                MediaType.APPLICATION_JSON_VALUE,
                MediaType.TEXT_HTML_VALUE
            ],
            value = ["/{value}"]
    )
    fun findByValue(
            @RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String,
            @PathVariable value: String
    ): Any {
        val entity = repository.findByValue(value).orElseThrow {
            EntityNotFoundException("Tag with value \"$value\" not found.")
        }

        if (acceptHeader.contains(MediaType.TEXT_HTML_VALUE)) {
            return ModelAndView("tag")
                    .addObject("tag", entity)
        }

        return assembler.toModel(entity)
    }
}