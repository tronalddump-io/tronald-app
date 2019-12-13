package io.tronalddump.app.author

import javax.persistence.*

@Entity
@Table(name = "author")
open class AuthorEntity(
        @get:Id
        @get:Column(name = "author_id", nullable = false, insertable = false, updatable = false)
        open var authorId: String? = null,

        @get:Basic
        @get:Column(name = "bio")
        open var bio: String? = null,

        @get:Basic
        @get:Column(name = "created_at")
        open var createdAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "name")
        open var name: String? = null,

        @get:Basic
        @get:Column(name = "slug")
        open var slug: String? = null,

        @get:Basic
        @get:Column(name = "updated_at")
        open var updatedAt: java.sql.Timestamp? = null
)