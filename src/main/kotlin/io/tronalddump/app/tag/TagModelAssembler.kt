package io.tronalddump.app.tag

import org.springframework.hateoas.server.mvc.RepresentationModelAssemblerSupport
import org.springframework.stereotype.Component

@Component
class TagModelAssembler : RepresentationModelAssemblerSupport<TagEntity, TagModel>(TagController::class.java, TagModel::class.java) {

    override fun instantiateModel(entity: TagEntity): TagModel {
        return TagModel(
                entity.createdAt,
                entity.tagId,
                entity.updatedAt,
                entity.value
        )
    }

    override fun toModel(entity: TagEntity): TagModel {
        return this.createModelWithId(entity.tagId.toString(), entity)
    }
}