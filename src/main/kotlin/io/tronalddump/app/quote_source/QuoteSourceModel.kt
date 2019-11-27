package io.tronalddump.app.quote_source

import org.springframework.hateoas.RepresentationModel
import org.springframework.hateoas.server.core.Relation

@Relation(collectionRelation = "source")
class QuoteSourceModel(
        var createdAt: java.sql.Timestamp? = null,
        var filename: String? = null,
        var quoteSourceId: String? = null,
        var remarks: String? = null,
        var updatedAt: java.sql.Timestamp? = null,
        var url: String? = null
) : RepresentationModel<QuoteSourceModel>()