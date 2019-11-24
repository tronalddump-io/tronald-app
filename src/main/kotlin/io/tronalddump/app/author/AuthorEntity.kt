package io.tronalddump.app.author

import javax.persistence.*

@Entity
@Table(name = "author")
open class AuthorEntity(
        @get:Id
        @get:Column(name = "author_id", nullable = false, insertable = false, updatable = false)
        var authorId: String? = null,

        @get:Basic
        @get:Column(name = "bio", nullable = true)
        var bio: String? = null,

        @get:Basic
        @get:Column(name = "created_at", nullable = true)
        var createdAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "name", nullable = true)
        var name: String? = null,

        @get:Basic
        @get:Column(name = "slug", nullable = true)
        var slug: String? = null,

        @get:Basic
        @get:Column(name = "updated_at", nullable = true)
        var updatedAt: java.sql.Timestamp? = null
)