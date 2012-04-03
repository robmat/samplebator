package com.bator.ifonly.util;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.List;

import android.os.Handler;
import android.os.Message;
import android.util.Log;

import com.bator.ifonly.UploadVideoActivity;
import com.google.api.client.googleapis.GoogleHeaders;
import com.google.api.client.googleapis.auth.clientlogin.ClientLogin;
import com.google.api.client.googleapis.auth.clientlogin.ClientLogin.Response;
import com.google.api.client.googleapis.json.JsonCParser;
import com.google.api.client.http.GenericUrl;
import com.google.api.client.http.HttpParser;
import com.google.api.client.http.HttpRequest;
import com.google.api.client.http.HttpRequestFactory;
import com.google.api.client.http.HttpRequestInitializer;
import com.google.api.client.http.HttpResponse;
import com.google.api.client.http.HttpResponseException;
import com.google.api.client.http.HttpTransport;
import com.google.api.client.http.HttpUnsuccessfulResponseHandler;
import com.google.api.client.http.InputStreamContent;
import com.google.api.client.http.MultipartRelatedContent;
import com.google.api.client.http.javanet.NetHttpTransport;
import com.google.api.client.http.xml.XmlHttpParser;
import com.google.api.client.http.xml.atom.AtomContent;
import com.google.api.client.http.xml.atom.AtomParser;
import com.google.api.client.json.JsonFactory;
import com.google.api.client.json.jackson.JacksonFactory;
import com.google.api.client.util.Key;
import com.google.api.client.util.Value;
import com.google.api.client.xml.XmlNamespaceDictionary;

public class YoutubeService {
	public static final String YOUTUBE_GDATA_BASE_URL = "http://gdata.youtube.com";
	public static final String YOUTUBE_UPLOADS_GDATA_BASE_URL = "http://uploads.gdata.youtube.com";
	public static final String YOUTUBE_FEEDS_API_URL = YOUTUBE_GDATA_BASE_URL + "/feeds/api/";
	public static final String YOUTUBE_FEEDS_API_UPLOAD_URL = YOUTUBE_UPLOADS_GDATA_BASE_URL + "/feeds/api/users/default/uploads";
	public static final String USER_TOKEN = "[<USER_TOKEN>]";
	public static final String YOUTUBE_FEEDS_URL = "https://gdata.youtube.com/feeds/api/users/" + USER_TOKEN + "/uploads";
	/**
	 * From http://code.google.com/intl/en/apis/youtube/2.0/reference.html#
	 * API_Request_XML_Element_Definitions
	 */
	private static final XmlNamespaceDictionary YOUTUBE_NAMESPACE_DICT = new XmlNamespaceDictionary().set("", "http://www.w3.org/2005/Atom") // Atom
																																				// Syndication
																																				// Format
			.set("openSearch", "http://a9.com/-/spec/opensearch/1.1/") // Open
																		// Search
																		// Schema
			.set("media", "http://search.yahoo.com/mrss/") // Media RSS
			.set("yt", "http://gdata.youtube.com/schemas/2007") // YouTube XML
																// Schema
			.set("gd", "http://schemas.google.com/g/2005") // Google Data Schema
			.set("georss", "http://www.georss.org/georss") // GeoRSS
			.set("gml", "http://www.opengis.net/gml") // Geography Markup
														// Language
			.set("app", "http://www.w3.org/2007/app") // Atom Publishing
														// Protocol
			.set("batch", "http://schemas.google.com/gdata/batch"); // Google
																	// Data API
																	// Batch
																	// Processing
	private static final XmlNamespaceDictionary ERROR_NAMESPACE_DICT = new XmlNamespaceDictionary().set("", ""); // Just
																													// for
																													// mapping
																													// from
																													// ""
																													// namespace
																													// URI
																													// something
																													// !=
																													// null
																													// (to
																													// prevent
																													// a
																													// NPE)

	/**
	 * Stores the parsed error response
	 * 
	 * @author fhackenberger
	 */
	protected class YoutubeUnsuccessfulResponseHandler implements HttpUnsuccessfulResponseHandler {
		YoutubeErrors errors = null;

		public boolean handleResponse(HttpRequest request, HttpResponse response, boolean retrySupported) throws IOException {
			errors = response.parseAs(YoutubeErrors.class);
			return false;
		}
	}

	/**
	 * Represents an Atom formatted upload request for YouTube
	 * 
	 * @see http://code.google.com/intl/en/apis/youtube/2.0/
	 *      developers_guide_protocol_direct_uploading
	 *      .html#Sending_a_Direct_Upload_API_Request
	 * 
	 *      <entry xmlns="http://www.w3.org/2005/Atom"
	 *      xmlns:media="http://search.yahoo.com/mrss/"
	 *      xmlns:yt="http://gdata.youtube.com/schemas/2007"> <media:group>
	 *      <media:title type="plain">Bad Wedding Toast</media:title>
	 *      <media:description type="plain"> I gave a bad toast at my friend's
	 *      wedding. </media:description> <media:category
	 *      scheme="http://gdata.youtube.com/schemas/2007/categories.cat">People
	 *      </media:category> <media:keywords>toast, wedding</media:keywords>
	 *      </media:group> </entry>
	 * @author fhackenberger
	 */
	public static class UploadEntry {
		@Key("media:group")
		MediaGroup group = new MediaGroup();
	}

	/**
	 * @see UploadEntry
	 * @author fhackenberger
	 */
	public static class MediaGroup {
		@Key("yt:private")
		String ytPrivate;
		@Key("media:title")
		MediaAttribute title = new MediaAttribute();
		@Key("media:description")
		MediaAttribute description = new MediaAttribute();
		@Key("media:category")
		MediaCategory category = new MediaCategory();
		@Key("media:keywords")
		String keywords;
	}

	/**
	 * @see UploadEntry
	 * @author fhackenberger
	 */
	public static class MediaAttribute {
		@Key("@type")
		String type = "plain";
		@Key("text()")
		String value;
	}

	/**
	 * @see UploadEntry
	 * @author fhackenberger
	 */
	public static class MediaCategory {
		@Key("@scheme")
		String scheme = "http://gdata.youtube.com/schemas/2007/categories.cat";
		@Key("text()")
		String category;
	}

	/**
	 * Represents an error response from Youtube
	 * 
	 * @see http://code.google.com/intl/en/apis/youtube/2.0/
	 *      developers_guide_protocol_error_responses.html <errors> <error>
	 *      <domain>yt:validation</domain> <code>invalid_value</code> <location
	 *      type=
	 *      'xpath'>media:group/media:category[@scheme='http://gdata.youtube.com/schemas/2007/categories.cat']/text(
	 *      ) </location> </error> </errors>
	 * @author fhackenberger
	 * 
	 */
	public static class YoutubeErrors {
		@Key("error")
		List<YoutubeError> errors;
	}

	/**
	 * @see YoutubeErrors
	 * @author fhackenberger
	 */
	public static class YoutubeError {
		@Key
		String domain;
		@Key
		String code;
		@Key
		YoutubeErrorLocation location;

		@Override
		public String toString() {
			return "domain: " + domain + "; code: " + code + "; location: (" + location + ")";
		}
	}

	/**
	 * @see YoutubeError
	 * @author fhackenberger
	 */
	public static class YoutubeErrorLocation {
		@Key("@type")
		String type;
		@Key("text()")
		String location;

		@Override
		public String toString() {
			return "type: " + type + "; location: " + location;
		}
	}

	/**
	 * Represents a YouTube video feed
	 * 
	 * @see http://code.google.com/intl/en/apis/youtube/2.0/
	 *      developers_guide_protocol_understanding_video_feeds.html
	 * @author fhackenberger
	 */
	public static class VideoFeed {
		@Key
		List<Video> items;

		@Override
		public String toString() {
			return "Items: " + items;
		}
	}

	/**
	 * A single video entry
	 * 
	 * @see VideoFeed
	 * @author fhackenberger
	 */
	public static class Video {
		@Key
		String id;
		@Key
		String title;
		@Key
		String description;
		@Key
		Player player;
		@Key("link")
		List<Link> links;

		@Override
		public String toString() {
			return "Id: " + id + " Title: " + title + " Description: " + description + " Player: " + player + " Links: " + links;
		}
	}

	/**
	 * A related link for a {@link Video}
	 * 
	 * @see VideoFeed
	 * @author fhackenberger
	 */
	public static class Link {
		@Key("@rel")
		String rel;
		@Key("@href")
		String href;
		@Key("@type")
		String type;

		@Override
		public String toString() {
			return href;
		}
	}

	/**
	 * The URL for the YouTube video player for a {@link Video}
	 * 
	 * @see VideoFeed
	 * @author fhackenberger
	 */
	public static class Player {
		@Key("default")
		String defaultUrl;

		@Override
		public String toString() {
			return "DefaultURL: " + defaultUrl;
		}
	}

	public static class YouTubeUploadUrl extends GenericUrl {
		YouTubeUploadUrl() {
			super(YOUTUBE_FEEDS_API_UPLOAD_URL);
		}
	}

	public static class FeedsYouTubeUrl extends GenericUrl {
		@Key
		final String alt = "jsonc";
		@Key
		String author;
		@Key("max-results")
		Integer maxResults;
		@Key("q")
		String query;
		@Key("orderby")
		String orderby;
		FeedsYouTubeUrl(String endpoint) {
			super(YOUTUBE_FEEDS_API_URL + endpoint);
		}
	}

	@Value("${google.apiKey}")
	public String apiKey;
	@Value("${google.appName}")
	public String appName;
	@Value("${youtube.userName}")
	public String youtubeUser;
	@Value("${youtube.password}")
	public String youtubePassword;
	Response clientLoginResponse = null;
	HttpRequestFactory requestFactory;
	HttpTransport transport;
	HttpParser jsonParser;
	HttpParser atomParser;
	HttpParser xmlParser;
	HttpParser textXmlParser;

	public YoutubeService() {
		JsonFactory jsonFactory = new JacksonFactory();
		JsonCParser jParser = new JsonCParser();
		jParser.jsonFactory = jsonFactory;
		jsonParser = jParser;
		atomParser = new AtomParser();
		((AtomParser) atomParser).namespaceDictionary = YOUTUBE_NAMESPACE_DICT;
		xmlParser = new XmlHttpParser();
		((XmlHttpParser) xmlParser).namespaceDictionary = ERROR_NAMESPACE_DICT;
		// XmlHttpParser is for application/xml, but we need text/xml as well
		// (for error responses)
		textXmlParser = new XmlHttpParser();
		((XmlHttpParser) textXmlParser).namespaceDictionary = ERROR_NAMESPACE_DICT;
		((XmlHttpParser) textXmlParser).contentType = "text/xml";

		// set up the HTTP request factory
		transport = new NetHttpTransport();
		requestFactory = transport.createRequestFactory(new HttpRequestInitializer() {
			public void initialize(HttpRequest request) {
				request.addParser(jsonParser);
				request.addParser(atomParser);
				request.addParser(xmlParser);
				request.addParser(textXmlParser);
				// set up the Google headers
				GoogleHeaders headers = new GoogleHeaders();
				headers.setApplicationName(appName);
				headers.gdataVersion = "2";
				headers.gdataKey = apiKey;
				request.headers = headers;
				// If we have a login response, we use it as the authentication
				// header
				if (clientLoginResponse != null) {
					request.headers.authorization = GoogleHeaders.getGoogleLoginValue(clientLoginResponse.auth);
				}
			}
		});
	}

	/**
	 * Upload a new video
	 * 
	 * @param videoStream
	 *            Input stream for the video file
	 * @param videoFilename
	 *            Required for the YouTube upload request, probably for filetype
	 *            detection
	 * @return {@link Video}
	 * @throws DebatingServiceException
	 */
	public Video uploadVideo(Handler handler, InputStream videoStream, String videoFilename, String title, String description, String category, String keywords) throws DebatingServiceException {
		final String errMsg = "Exception while uploading Video: " + videoFilename;
		HttpRequest request = null;
		try {
			ensureLoggedIn();

			request = requestFactory.buildPostRequest(new YouTubeUploadUrl(), null);
			InputStreamContent videoContent = new InputStreamContent();
			videoContent.inputStream = videoStream;
			videoContent.type = "application/octet-stream";

			// Describes the video
			AtomContent atomContent = new AtomContent();
			atomContent.namespaceDictionary = YOUTUBE_NAMESPACE_DICT;
			UploadEntry uploadEntry = new UploadEntry();
			uploadEntry.group.title.value = title;
			uploadEntry.group.description.value = description;
			uploadEntry.group.category.category = category;
			uploadEntry.group.keywords = keywords;
			uploadEntry.group.ytPrivate = "";
			atomContent.entry = uploadEntry;

			MultipartRelatedContent multiPartContent = MultipartRelatedContent.forRequest(request);
			multiPartContent.parts.add(atomContent);
			multiPartContent.parts.add(videoContent);
//			ByteArrayOutputStream bais = new ByteArrayOutputStream();
//			multiPartContent.writeTo(bais);
//			String requestStr = IOUtils.toString(new ByteArrayInputStream(bais.toByteArray()));
//			System.out.println(requestStr);
			request.content = multiPartContent;
			GoogleHeaders gHeaders = (GoogleHeaders) request.headers;
			gHeaders.slug = GoogleHeaders.SLUG_ESCAPER.escape(videoFilename);
			request.unsuccessfulResponseHandler = new YoutubeUnsuccessfulResponseHandler();
			HttpResponse response = request.execute();
			handler.sendEmptyMessage(UploadVideoActivity.UPLOAD_VIDEO_SUCCESS_MSG_WHAT);
			return response.parseAs(Video.class);
		} catch (HttpResponseException e) {
			YoutubeError error = ((YoutubeUnsuccessfulResponseHandler) request.unsuccessfulResponseHandler).errors.errors.get(0);
			String msg = errMsg + " " + error;
			Log.e("uploadVideo", msg);
			Message message = new Message();
			message.what = UploadVideoActivity.UPLOAD_VIDEO_ERROR_MSG_WHAT;
			message.obj = msg;
			handler.sendMessage(message);
			throw new DebatingServiceException(msg);
		} catch (IOException e) {
			Log.e("uploadVideo", errMsg, e);
		}
		throw new DebatingServiceException(errMsg);
	}

	/**
	 * Delete an existing video
	 * 
	 * @param video
	 *            The {@link Video} to delete. Must contain a {@link Link} with
	 *            {@link Link#rel} == 'edit'.
	 * @throws DebatingServiceException
	 */
	public void deleteVideo(Video video) throws DebatingServiceException {
		final String errMsg = "Exception while deleting Video " + video;
		HttpRequest request = null;
		try {
			Link editLink = null;
			for (Link link : video.links) {
				if (link.rel.equals("edit")) {
					editLink = link;
					break;
				}
			}
			if (editLink == null)
				throw new DebatingServiceException("No edit link found in video " + video);
			ensureLoggedIn();

			request = requestFactory.buildDeleteRequest(new GenericUrl(editLink.href));
			request.unsuccessfulResponseHandler = new YoutubeUnsuccessfulResponseHandler();
			request.execute();
		} catch (HttpResponseException e) {
			YoutubeError error = ((YoutubeUnsuccessfulResponseHandler) request.unsuccessfulResponseHandler).errors.errors.get(0);
			String msg = errMsg + "; " + error;
			Log.e("uploadVideo", msg);
			throw new DebatingServiceException(msg);
		} catch (IOException e) {
			Log.e("uploadVideo", errMsg, e);
		}
	}
	
	public VideoFeed queryVideoFeeds(Handler handler, String query, String user, String orderBy) throws DebatingServiceException {
		final String errMsg = "Exception while retrieving video feed for user " + youtubeUser;
		try {
			// build the YouTube URL
			ensureLoggedIn();
			String urlStr = YOUTUBE_FEEDS_URL.replace(USER_TOKEN, user);
			urlStr += "?max-results=50";
			urlStr += "&orderby=" + orderBy;
			urlStr += "&q=" + query;
			GenericUrl url = new GenericUrl(urlStr);
			
			// build the HTTP GET request
			HttpRequest request = requestFactory.buildGetRequest(url);
			// execute the request and the parse video feed
			return request.execute().parseAs(VideoFeed.class);
		} catch (HttpResponseException e) {
			Log.e("uploadVideo", errMsg, e);
		} catch (IOException e) {
			Log.e("uploadVideo", errMsg, e);
		}
		throw new DebatingServiceException(errMsg);
	}

	void ensureLoggedIn() throws IOException {
		ClientLogin authenticator = new ClientLogin();
		authenticator.transport = transport;
		authenticator.authTokenType = "youtube";
		authenticator.username = youtubeUser;
		authenticator.password = youtubePassword;
		clientLoginResponse = authenticator.authenticate();
	}
	
	public static void main(String[] args) throws DebatingServiceException, FileNotFoundException {
		String videoFilename = "/tmp/294914.flv";
		YoutubeService service = new YoutubeService();
		service.apiKey = "key=YOURAPIKEY";
		service.appName = "Youtube-Sample/1.0";
		service.youtubeUser = "USERNAME";
		service.youtubePassword = "SECRETPASSWORD";
		System.out.println(service.queryVideoFeeds(null, null, null, null));
		Video video = service.uploadVideo(new Handler(), new FileInputStream(new File(videoFilename)), videoFilename, "Test title", "Test description", "People", "keyword1, keyword2");
		System.out.println(video);
		service.deleteVideo(video);
	}
}
