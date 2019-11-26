package io.tronalddump.app.quote_source

import org.springframework.data.jpa.repository.JpaRepository
import org.springframework.stereotype.Repository

@Repository
interface QuoteSourceRepository : JpaRepository<QuoteSourceEntity, String>
