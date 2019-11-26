package io.tronalddump.app.author

import javax.persistence.*

@Entity
@Table(name = "author")
open class AuthorEntity(
        @get:Id
        @get:Column(name = "author_id", nullable = false, insertable = false, updatable = false)
        var authorId: String? = null,

        @get:Basic
        @get:Column(name = "bio")
        var bio: String? = null,

        @get:Basic
        @get:Column(name = "created_at")
        var createdAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "name")
        var name: String? = null,

        @get:Basic
        @get:Column(name = "slug")
        var slug: String? = null,

        @get:Basic
        @get:Column(name = "updated_at")
        var updatedAt: java.sql.Timestamp? = null
)