package io.tronalddump.app.tag

import javax.persistence.*

@Entity
@Table(name = "tag")
open class TagEntity(
        @get:Basic
        @get:Column(name = "created_at")
        var createdAt: java.sql.Timestamp? = null,

        @get:Id
        @get:Column(name = "tag_id", nullable = false, insertable = false, updatable = false)
        var tagId: String? = null,

        @get:Basic
        @get:Column(name = "updated_at")
        var updatedAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "value")
        var value: String? = null
)

