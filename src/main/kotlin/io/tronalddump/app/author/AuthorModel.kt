package io.tronalddump.app.author

import org.springframework.hateoas.RepresentationModel
import org.springframework.hateoas.server.core.Relation
import java.sql.Timestamp

@Relation(collectionRelation = "author")
data class AuthorModel(
        val authorId: String? = null,
        val bio: String? = null,
        val createdAt: Timestamp? = null,
        val name: String? = null,
        val slug: String? = null,
        val updatedAt: Timestamp? = null
) : RepresentationModel<AuthorModel>()