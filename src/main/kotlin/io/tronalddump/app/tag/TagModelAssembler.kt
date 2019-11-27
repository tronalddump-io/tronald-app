package io.tronalddump.app.tag

import org.springframework.hateoas.server.mvc.RepresentationModelAssemblerSupport
import org.springframework.stereotype.Component

@Component
class TagModelAssembler : RepresentationModelAssemblerSupport<TagEntity, TagModel>(TagController::class.java, TagModel::class.java) {

    override fun toModel(entity: TagEntity): TagModel {
        val model = this.createModelWithId(entity.tagId.toString(), entity)

        model.createdAt = entity.createdAt
        model.tagId = entity.tagId
        model.updatedAt = entity.updatedAt
        model.value = entity.value

        return model
    }
}