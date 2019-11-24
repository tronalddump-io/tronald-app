package io.tronalddump.app.quote

import org.springframework.data.jpa.repository.JpaRepository
import org.springframework.stereotype.Repository

@Repository
interface
QuoteRepository : JpaRepository<QuoteEntity, String>
