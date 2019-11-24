package io.tronalddump.app.quote_source

import javax.persistence.*

@Entity
@Table(name = "quote_source")
open class QuoteSourceEntity(
        @get:Basic
        @get:Column(name = "created_at", nullable = true)
        var createdAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "filename", nullable = true)
        var filename: String? = null,

        @get:Id
        @get:Column(name = "quote_source_id", nullable = false, insertable = false, updatable = false)
        var quoteSourceId: String? = null,

        @get:Basic
        @get:Column(name = "remarks", nullable = true)
        var remarks: String? = null,

        @get:Basic
        @get:Column(name = "updated_at", nullable = true)
        var updatedAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "url", nullable = true)
        var url: String? = null
)
