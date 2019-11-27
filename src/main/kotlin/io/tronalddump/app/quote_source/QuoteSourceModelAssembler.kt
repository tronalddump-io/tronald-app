package io.tronalddump.app.quote_source

import org.springframework.hateoas.server.mvc.RepresentationModelAssemblerSupport
import org.springframework.stereotype.Component

@Component
class QuoteSourceModelAssembler : RepresentationModelAssemblerSupport<QuoteSourceEntity, QuoteSourceModel>(QuoteSourceController::class.java, QuoteSourceModel::class.java) {

    override fun toModel(entity: QuoteSourceEntity): QuoteSourceModel {
        val model = this.createModelWithId(entity.quoteSourceId.toString(), entity)

        model.createdAt = entity.createdAt
        model.filename = entity.filename
        model.quoteSourceId = entity.quoteSourceId
        model.remarks = entity.remarks
        model.updatedAt = entity.updatedAt
        model.url = entity.url

        return model
    }
}