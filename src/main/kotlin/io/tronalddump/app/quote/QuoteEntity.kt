package io.tronalddump.app.quote

import io.tronalddump.app.author.AuthorEntity
import io.tronalddump.app.quote_source.QuoteSourceEntity
import io.tronalddump.app.tag.TagEntity
import java.sql.Timestamp
import javax.persistence.*

@Entity
@Table(name = "quote")
data class QuoteEntity(
        @get:Basic
        @get:Column(name = "appeared_at", nullable = true)
        var appearedAt: Timestamp? = null,

        @get:JoinColumn(name = "author_id", referencedColumnName = "author_id")
        @get:ManyToOne(fetch = FetchType.EAGER)
        var author: AuthorEntity? = null,

        @get:Basic
        @get:Column(name = "created_at", nullable = true)
        var createdAt: Timestamp? = null,

        @get:Column(name = "quote_id", nullable = false, insertable = false, updatable = false)
        @get:Id
        var quoteId: String? = null,

        @get:JoinColumn(name = "quote_source_id", referencedColumnName = "quote_source_id")
        @get:ManyToOne(fetch = FetchType.EAGER)
        var source: QuoteSourceEntity? = null,

        @get:JoinTable(
                name = "quote_tag",
                joinColumns = [JoinColumn(name = "quote_id")],
                inverseJoinColumns = [JoinColumn(name = "tag_id")]
        )
        @get:OneToMany(fetch = FetchType.EAGER)
        var tags: List<TagEntity>? = emptyList(),

        @get:Basic
        @get:Column(name = "updated_at", nullable = true)
        var updatedAt: Timestamp? = null,

        @get:Basic
        @get:Column(name = "value", nullable = true)
        var value: String? = null
)

