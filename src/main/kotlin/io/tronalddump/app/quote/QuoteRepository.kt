package io.tronalddump.app.quote

import org.springframework.data.domain.Page
import org.springframework.data.domain.Pageable
import org.springframework.data.jpa.repository.JpaRepository
import org.springframework.data.jpa.repository.Query
import org.springframework.stereotype.Repository
import java.util.*

@Repository
interface QuoteRepository : JpaRepository<QuoteEntity, String> {

    fun findByValueContaining(query: String, pageable: Pageable): Page<QuoteEntity>

    @Query(
            value = "SELECT q.* FROM quote q ORDER BY RANDOM() LIMIT 1",
            nativeQuery = true
    )
    fun randomQuote(): Optional<QuoteEntity>
}
