package io.tronalddump.app.search

import io.swagger.v3.oas.annotations.Operation
import io.tronalddump.app.Url
import io.tronalddump.app.exception.EntityNotFoundException
import io.tronalddump.app.quote.QuoteEntity
import io.tronalddump.app.quote.QuoteRepository
import io.tronalddump.app.tag.TagRepository
import org.springframework.data.domain.Page
import org.springframework.data.domain.PageRequest
import org.springframework.data.domain.Pageable
import org.springframework.hateoas.MediaTypes
import org.springframework.hateoas.server.mvc.WebMvcLinkBuilder
import org.springframework.hateoas.server.mvc.WebMvcLinkBuilder.linkTo
import org.springframework.http.HttpHeaders
import org.springframework.http.HttpStatus
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*
import org.springframework.web.server.ResponseStatusException
import org.springframework.web.servlet.ModelAndView

@RequestMapping(value = [Url.SEARCH])
@RestController
class SearchController(
        private val assembler: PageModelAssembler,
        private val quoteRepository: QuoteRepository,
        private val tagRepository: TagRepository
) {

    @Operation(summary = "Search quotes by query or tags", tags = ["quote"])
    @ResponseBody
    @RequestMapping(
            headers = [
                "${HttpHeaders.ACCEPT}=${MediaType.APPLICATION_JSON_VALUE}",
                "${HttpHeaders.ACCEPT}=${MediaType.TEXT_HTML_VALUE}",
                "${HttpHeaders.ACCEPT}=${MediaTypes.HAL_JSON_VALUE}"
            ],
            method = [RequestMethod.GET],
            produces = [
                MediaType.APPLICATION_JSON_VALUE,
                MediaType.TEXT_HTML_VALUE
            ],
            value = ["/quote"]
    )
    fun quote(
            @RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String,
            @RequestParam("query", required = false, defaultValue = "") query: String,
            @RequestParam("tag", required = false, defaultValue = "") tag: String,
            @RequestParam(value = "page", defaultValue = "0") pageNumber: Int
    ): Any {
        val page: Pageable = PageRequest.of(pageNumber, 10)

        if (query.isNotEmpty()) {
            val result: Page<QuoteEntity> = quoteRepository.findByValueContaining(query, page)
            val model: PageModel = assembler.toModel(result)

            val linkBuilder: WebMvcLinkBuilder = linkTo(this::class.java)
            model.add(linkBuilder.slash("quote/?query=${query}&page=${pageNumber}").withSelfRel())
            model.add(linkBuilder.slash("quote/?query=${query}&page=${page.first().pageNumber}").withRel("first"))
            model.add(linkBuilder.slash("quote/?query=${query}&page=${page.previousOrFirst().pageNumber}").withRel("prev"))
            model.add(linkBuilder.slash("quote/?query=${query}&page=${page.next().pageNumber}").withRel("next"))
            model.add(linkBuilder.slash("quote/?query=${query}&page=${result.totalPages}").withRel("last"))

            if (acceptHeader.contains(MediaType.TEXT_HTML_VALUE)) {
                return ModelAndView("search")
                        .addObject("model", model)
                        .addObject("query", query)
                        .addObject("result", result)
            }

            return model
        }

        if (tag.isNotEmpty()) {
            val entity = tagRepository.findByValue(tag).orElseThrow {
                EntityNotFoundException("Tag with value \"$tag\" not found.")
            }

            val result: Page<QuoteEntity> = quoteRepository.findByTagsEquals(entity, page)
            val model: PageModel = assembler.toModel(result)

            val linkBuilder: WebMvcLinkBuilder = linkTo(this::class.java)
            model.add(linkBuilder.slash("quote/?tag=${tag}&page=${pageNumber}").withSelfRel())
            model.add(linkBuilder.slash("quote/?tag=${tag}&page=${page.first().pageNumber}").withRel("first"))
            model.add(linkBuilder.slash("quote/?tag=${tag}&page=${page.previousOrFirst().pageNumber}").withRel("prev"))
            model.add(linkBuilder.slash("quote/?tag=${tag}&page=${page.next().pageNumber}").withRel("next"))
            model.add(linkBuilder.slash("quote/?tag=${tag}&page=${result.totalPages}").withRel("last"))

            return model
        }

        throw ResponseStatusException(
                HttpStatus.BAD_REQUEST,
                "Either a query or tag parameter must be provided"
        )
    }
}
