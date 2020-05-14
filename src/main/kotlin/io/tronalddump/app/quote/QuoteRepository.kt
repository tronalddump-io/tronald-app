package io.tronalddump.app.quote

import io.tronalddump.app.tag.TagEntity
import org.springframework.data.domain.Page
import org.springframework.data.domain.Pageable
import org.springframework.data.jpa.repository.JpaRepository
import org.springframework.data.jpa.repository.Query
import org.springframework.stereotype.Repository
import java.util.*

@Repository
interface QuoteRepository : JpaRepository<QuoteEntity, String> {

    fun findByTagsEquals(tag: TagEntity, pageable: Pageable): Page<QuoteEntity>

    fun findByValueIgnoreCaseContaining(query: String, pageable: Pageable): Page<QuoteEntity>

    @Query(
            value = "SELECT q.* FROM quote q ORDER BY RANDOM() LIMIT 1",
            nativeQuery = true
    )
    fun randomQuote(): Optional<QuoteEntity>
}
