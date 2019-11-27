package io.tronalddump.app.tag

import org.springframework.hateoas.RepresentationModel
import org.springframework.hateoas.server.core.Relation

@Relation(collectionRelation = "tag")
class TagModel(
        val createdAt: java.sql.Timestamp? = null,
        val tagId: String? = null,
        val updatedAt: java.sql.Timestamp? = null,
        val value: String? = null
) : RepresentationModel<TagModel>()