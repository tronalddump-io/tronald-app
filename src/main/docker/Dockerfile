FROM centos:centos7

ENV JAVA_VERSION="1.8.0-openjdk"

ENV IMAGEMAGICK_VERSION="7.0.9-8"
ENV IMAGEMAGICK_LIB_RPM_URL="https://imagemagick.org/download/linux/CentOS/x86_64/ImageMagick-libs-${IMAGEMAGICK_VERSION}.x86_64.rpm"
ENV IMAGEMAGICK_RPM_URL="https://imagemagick.org/download/linux/CentOS/x86_64/ImageMagick-${IMAGEMAGICK_VERSION}.x86_64.rpm"

RUN curl -s -S $IMAGEMAGICK_LIB_RPM_URL -o imagemagick-lib.rpm && \
    curl -s -S $IMAGEMAGICK_RPM_URL -o imagemagick.rpm

RUN yum -y update && \
    yum -y install "java-${JAVA_VERSION}" && \
    yum localinstall -y imagemagick*.rpm && \
    rm -f imagemagick*.rpm && \
    yum clean all

ARG JAR_FILE
COPY ${JAR_FILE} app.jar

# This is a bit of a hack but it ensures that the MemeGenerator can pass file
# references to the ImageMagick tools without having to stream everything back
# and forth as we are running in a JAR.
RUN mkdir -p "src/main/resources/meme/font/"
COPY "meme/font/" "src/main/resources/meme/font/"

RUN mkdir -p "src/main/resources/meme/image/"
COPY "meme/image/" "src/main/resources/meme/image/"

CMD ["sh","-c","java -Dserver.port=$PORT -Xmx300m -Xss512k -XX:CICompilerCount=2 -Dfile.encoding=UTF-8 -XX:+UseContainerSupport -Djava.security.egd=file:/dev/./urandom -jar /app.jar"]