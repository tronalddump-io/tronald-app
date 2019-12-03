package io.tronalddump.app.tag

import org.springframework.data.jpa.repository.JpaRepository
import org.springframework.stereotype.Repository
import java.util.*

@Repository
interface TagRepository : JpaRepository<TagEntity, String> {

    fun findByValue(value: String): Optional<TagEntity>
}
