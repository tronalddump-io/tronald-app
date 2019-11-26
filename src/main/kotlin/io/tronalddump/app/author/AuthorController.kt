package io.tronalddump.app.author

import io.tronalddump.app.Url
import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.hateoas.EntityModel
import org.springframework.hateoas.MediaTypes.HAL_JSON_VALUE
import org.springframework.hateoas.server.mvc.WebMvcLinkBuilder.linkTo
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*

@RequestMapping(value = [Url.AUTHOR])
@RestController
class AuthorController(
        private val repository: AuthorRepository
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
    ): EntityModel<AuthorEntity> {
        val entity = repository.findById(id).orElseThrow {
            EntityNotFoundException("Author with id \"$id\" not found.")
        }

        if (acceptHeader != HAL_JSON_VALUE) {
            return EntityModel(entity)
        }

        val selfRel = linkTo(this::class.java)
                .slash(entity.authorId)
                .withSelfRel()

        return EntityModel<AuthorEntity>(entity)
                .add(selfRel)
    }
}