package io.tronalddump.app.quote_source

import javax.persistence.*

@Entity
@Table(name = "quote_source")
open class QuoteSourceEntity(
        @get:Basic
        @get:Column(name = "created_at")
        var createdAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "filename")
        var filename: String? = null,

        @get:Id
        @get:Column(name = "quote_source_id", nullable = false, insertable = false, updatable = false)
        var quoteSourceId: String? = null,

        @get:Basic
        @get:Column(name = "remarks")
        var remarks: String? = null,

        @get:Basic
        @get:Column(name = "updated_at")
        var updatedAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "url")
        var url: String? = null
)
