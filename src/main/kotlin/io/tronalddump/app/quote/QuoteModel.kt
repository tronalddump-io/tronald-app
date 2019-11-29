package io.tronalddump.app.quote

import com.fasterxml.jackson.annotation.JsonUnwrapped
import org.springframework.hateoas.CollectionModel
import org.springframework.hateoas.RepresentationModel
import org.springframework.hateoas.server.core.Relation
import java.sql.Timestamp

@Relation(collectionRelation = "quote")
data class QuoteModel(
        val appearedAt: Timestamp? = null,
        val createdAt: Timestamp? = null,
        val quoteId: String? = null,
        val tags: List<String?>? = null,
        val updatedAt: Timestamp? = null,
        val value: String? = null,

        @JsonUnwrapped
        val embedded: CollectionModel<RepresentationModel<*>?>? = null
) : RepresentationModel<QuoteModel>()