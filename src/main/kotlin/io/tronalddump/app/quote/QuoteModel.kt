package io.tronalddump.app.quote

import com.fasterxml.jackson.annotation.JsonUnwrapped
import org.springframework.hateoas.CollectionModel
import org.springframework.hateoas.RepresentationModel
import org.springframework.hateoas.server.core.Relation
import java.sql.Timestamp

@Relation(collectionRelation = "quote")
data class QuoteModel(
        var appearedAt: Timestamp? = null,
        var createdAt: Timestamp? = null,
        var quoteId: String? = null,
        var tags: List<String?>? = null,
        var value: String? = null,

        @JsonUnwrapped
        var embedded: CollectionModel<RepresentationModel<*>?>? = null
) : RepresentationModel<QuoteModel>()