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
        val model = this.createModelWithId(entity.quoteId.toString(), entity)

        model.appearedAt = entity.appearedAt
        model.createdAt = entity.createdAt
        model.quoteId = entity.quoteId
        model.tags = entity.tags?.map { it.value } ?: emptyList()
        model.value = entity.value

        val author = entity.author?.let { authorModelAssembler.toModel(it) }
        val source = entity.source?.let { quoteSourceModelAssembler.toModel(it) }
        model.embedded = CollectionModel(
                Arrays.asList(author, source)
        )

        return model
    }
}