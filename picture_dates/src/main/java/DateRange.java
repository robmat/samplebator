import java.io.IOException;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.Locale;
import java.util.Map;

import com.fasterxml.jackson.databind.ObjectMapper;

public class DateRange {
    public static void main(String[] args) throws IOException {
        Map<String, Map<String, Map<String, String>>> map = new ObjectMapper().readValue(Paths.get("result.json").toFile(), Map.class);
        for (Map.Entry<String, Map<String, Map<String, String>>> folder : map.entrySet()) {
            Path folderPath = Paths.get(folder.getKey());

            LocalDate max = null;
            LocalDate min = null;

            for (Map<String, String> fileInFolder : folder.getValue().values()) {
                if (fileInFolder.isEmpty()) {
                   continue;
                }

                boolean isBenq = false;
                for (Map.Entry<String, String> value : fileInFolder.entrySet()) {
                    if (value.getKey().contains("[Exif IFD0] - Make") && value.getValue().contains("BenQ")) {
                        isBenq = true;
                    }
                }
                LocalDate date = null;
                for (Map.Entry<String, String> value : fileInFolder.entrySet()) {
                    if (!isBenq && value.getKey().contains("Date/Time Original")) {
                        date = LocalDate.parse(value.getValue(), DateTimeFormatter.ofPattern("yyyy:MM:dd HH:mm:ss"));
                    }
                    date = parseModifiedDate(isBenq, date, value);
                }

                if (date == null) {
                    for (Map.Entry<String, String> value : fileInFolder.entrySet()) {
                        date = parseModifiedDate(true, date, value);
                    }
                }
                if (max == null || max.compareTo(date) < 0) {
                    max = date;
                }
                if (min == null || min.compareTo(date) > 0) {
                    min = date;
                }
            }

            System.out.format("%-80s %s %s", folderPath, min, max);
            System.out.println();
        }
    }

    private static LocalDate parseModifiedDate(boolean isBenq, LocalDate date, Map.Entry<String, String> value) {
        if (isBenq && value.getKey().contains("[File] - File Modified Date")) {
            String value1 = value.getValue();
            if (value1.contains("+01:00 ")) {
                value1 = value1.replace("+01:00 ", "");
            }
            if (value1.contains("+02:00 ")) {
                value1 = value1.replace("+02:00 ", "");
            }
            date = LocalDate.parse(value1, DateTimeFormatter.ofPattern("EEE MMM dd HH:mm:ss yyyy", new Locale("pl", "PL")));
        }
        return date;
    }
}
