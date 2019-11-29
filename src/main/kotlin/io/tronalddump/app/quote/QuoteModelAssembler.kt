package io.tronalddump.app.quote

import io.tronalddump.app.author.AuthorModelAssembler
import io.tronalddump.app.quote_source.QuoteSourceModelAssembler
import org.springframework.hateoas.CollectionModel
import org.springframework.hateoas.server.mvc.RepresentationModelAssemblerSupport
import org.springframework.stereotype.Component
import java.util.*

@Component
class QuoteModelAssembler(
        private val authorModelAssembler: AuthorModelAssembler,
        private val quoteSourceModelAssembler: QuoteSourceModelAssembler
) : RepresentationModelAssemblerSupport<QuoteEntity, QuoteModel>(QuoteController::class.java, QuoteModel::class.java) {

    override fun toModel(entity: QuoteEntity): QuoteModel {
        return this.createModelWithId(entity.quoteId.toString(), entity)
    }

    override fun instantiateModel(entity: QuoteEntity): QuoteModel {
        val author = entity.author?.let { authorModelAssembler.toModel(it) }
        val source = entity.source?.let { quoteSourceModelAssembler.toModel(it) }

        return QuoteModel(
                entity.appearedAt,
                entity.createdAt,
                entity.quoteId,
                entity.tags?.map { it.value } ?: emptyList(),
                entity.updatedAt,
                entity.value,
                CollectionModel(
                        Arrays.asList(author, source)
                )
        )
    }
}