package io.tronalddump.app.author

import org.springframework.hateoas.server.mvc.RepresentationModelAssemblerSupport
import org.springframework.stereotype.Component

@Component
class AuthorModelAssembler : RepresentationModelAssemblerSupport<AuthorEntity, AuthorModel>(AuthorController::class.java, AuthorModel::class.java) {

    override fun toModel(entity: AuthorEntity): AuthorModel {
        return this.createModelWithId(entity.authorId.toString(), entity)
    }

    override fun instantiateModel(entity: AuthorEntity): AuthorModel {
        return AuthorModel(
                entity.authorId,
                entity.bio,
                entity.createdAt,
                entity.name,
                entity.slug,
                entity.updatedAt
        )
    }
}