package io.tronalddump.app.author

import org.springframework.hateoas.RepresentationModel
import org.springframework.hateoas.server.core.Relation
import java.sql.Timestamp

@Relation(collectionRelation = "author")
data class AuthorModel(
        var authorId: String? = null,
        var bio: String? = null,
        var createdAt: Timestamp? = null,
        var name: String? = null,
        var slug: String? = null,
        var updatedAt: Timestamp? = null
) : RepresentationModel<AuthorModel>()