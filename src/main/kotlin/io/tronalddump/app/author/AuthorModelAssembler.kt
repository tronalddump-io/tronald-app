package io.tronalddump.app.author

import org.springframework.hateoas.server.mvc.RepresentationModelAssemblerSupport
import org.springframework.stereotype.Component

@Component
class AuthorModelAssembler : RepresentationModelAssemblerSupport<AuthorEntity, AuthorModel>(AuthorController::class.java, AuthorModel::class.java) {

    override fun toModel(entity: AuthorEntity): AuthorModel {
        val model = this.createModelWithId(entity.authorId.toString(), entity)

        model.authorId = entity.authorId
        model.bio = entity.bio
        model.createdAt = entity.createdAt
        model.name = entity.name
        model.slug = entity.slug
        model.updatedAt = entity.updatedAt

        return model
    }
}