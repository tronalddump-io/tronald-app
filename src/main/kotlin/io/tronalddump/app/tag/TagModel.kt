package io.tronalddump.app.tag

import org.springframework.hateoas.RepresentationModel
import org.springframework.hateoas.server.core.Relation

@Relation(collectionRelation = "tag")
class TagModel(
        var createdAt: java.sql.Timestamp? = null,
        var tagId: String? = null,
        var updatedAt: java.sql.Timestamp? = null,
        var value: String? = null
) : RepresentationModel<TagModel>()