package io.tronalddump.app.tag

import io.tronalddump.app.search.PageModel
import org.springframework.hateoas.CollectionModel
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

    fun toPageModel(list: List<TagEntity>): PageModel {
        return PageModel(
                list.size,
                list.size.toLong(),
                CollectionModel(
                        toCollectionModel(list)
                )
        )
    }
}