package io.tronalddump.app.quote_source

import javax.persistence.*

@Entity
@Table(name = "quote_source")
open class QuoteSourceEntity(
        @get:Basic
        @get:Column(name = "created_at")
        open var createdAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "filename")
        open var filename: String? = null,

        @get:Id
        @get:Column(name = "quote_source_id", nullable = false, insertable = false, updatable = false)
        open var quoteSourceId: String? = null,

        @get:Basic
        @get:Column(name = "remarks")
        open var remarks: String? = null,

        @get:Basic
        @get:Column(name = "updated_at")
        open var updatedAt: java.sql.Timestamp? = null,

        @get:Basic
        @get:Column(name = "url")
        open var url: String? = null
)
