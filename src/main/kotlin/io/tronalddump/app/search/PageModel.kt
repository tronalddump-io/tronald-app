package io.tronalddump.app.search

import com.fasterxml.jackson.annotation.JsonUnwrapped
import org.springframework.hateoas.CollectionModel
import org.springframework.hateoas.RepresentationModel

data class PageModel(
        val count: Int? = null,
        val total: Long? = null,

        @JsonUnwrapped
        val embedded: CollectionModel<RepresentationModel<*>?>? = null
) : RepresentationModel<PageModel>()