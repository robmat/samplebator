import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

import com.drew.imaging.ImageMetadataReader;
import com.drew.metadata.Directory;
import com.drew.metadata.Tag;
import com.fasterxml.jackson.databind.ObjectMapper;
import org.apache.tika.config.TikaConfig;
import org.apache.tika.io.TikaInputStream;
import org.apache.tika.metadata.Metadata;
import org.apache.tika.mime.MediaType;

public class MatadataParser {
    public static void main(String[] args) throws Exception {
        TikaConfig tika = new TikaConfig();
        Map<String, Map<String, Map<String, String>>> result = new HashMap<>();

        List<Path> allMainFolders = Files.list(Paths.get("D:", "Box Sync", "zdjęcia")).collect(Collectors.toList());

        for (Path mainFolder : allMainFolders) {

            if (mainFolder.getFileName().toString().equals("stare_zdjecia")) {
                continue;
            }

            Map<String, Map<String, String>> mainFolderResult = new HashMap<>();
            result.put(mainFolder.toString(), mainFolderResult);

            List<Path> filesInMainFolder = Files.list(mainFolder).collect(Collectors.toList());

            for (Path fileInMainFolder : filesInMainFolder) {

                List<Path> filesDetected = new ArrayList<>();

                if (Files.isDirectory(fileInMainFolder)) {
                    filesDetected.addAll(Files.list(fileInMainFolder).collect(Collectors.toList()));
                } else {
                    filesDetected.add(fileInMainFolder);
                }

                for (Path fileDetected : filesDetected) {
                    Metadata metadata = new Metadata();
                    metadata.set(Metadata.RESOURCE_NAME_KEY, fileDetected.getFileName().toString());
                    MediaType mimetype = tika.getDetector().detect(TikaInputStream.get(fileDetected), metadata);
                    HashMap<String, String> singleFileResult = new HashMap<>();
                    mainFolderResult.put(fileDetected.toString(), singleFileResult);

                    if (mimetype.toString().startsWith("video")) {

                    } else if (mimetype.toString().startsWith("image")) {
                        print(ImageMetadataReader.readMetadata(fileDetected.toFile()), singleFileResult);
                    } else {
                        print(ImageMetadataReader.readMetadata(fileDetected.toFile()), singleFileResult);
                    }
                }
            }
        }

        byte[] bytes = new ObjectMapper().writerWithDefaultPrettyPrinter().writeValueAsBytes(result);
        Files.write(Paths.get("result.json"), bytes);
    }

    public static void print(com.drew.metadata.Metadata metadata, HashMap<String, String> singleFileResult) {
        for (Directory directory : metadata.getDirectories()) {
            for (Tag tag : directory.getTags()) {
                System.out.println(String.format("[%s] - %s = %s",
                        directory.getName(), tag.getTagName(), tag.getDescription()));
                singleFileResult.put(String.format("[%s] - %s",
                        directory.getName(), tag.getTagName()), tag.getDescription());
            }
            if (directory.hasErrors()) {
                for (String error : directory.getErrors()) {
                    System.err.format(String.format("ERROR: %s", error));
                    singleFileResult.put(String.format("ERROR: %s", error), String.format("ERROR: %s", error));
                }
            }
        }
    }
}
