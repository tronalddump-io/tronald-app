package io.tronalddump.app.quote_source

import org.springframework.hateoas.server.mvc.RepresentationModelAssemblerSupport
import org.springframework.stereotype.Component

@Component
class QuoteSourceModelAssembler : RepresentationModelAssemblerSupport<QuoteSourceEntity, QuoteSourceModel>(QuoteSourceController::class.java, QuoteSourceModel::class.java) {

    override fun toModel(entity: QuoteSourceEntity): QuoteSourceModel {
        return this.createModelWithId(entity.quoteSourceId.toString(), entity)
    }

    override fun instantiateModel(entity: QuoteSourceEntity): QuoteSourceModel {
        return QuoteSourceModel(
                entity.createdAt,
                entity.filename,
                entity.quoteSourceId,
                entity.remarks,
                entity.updatedAt,
                entity.url
        )
    }
}