#import "SaopRequest.h"

@implementation SaopRequest

@synthesize username, password, domain, delegate, request, url, envelope, action;

- (SaopRequest*) initWithUrl: (NSURL*) url_ username: (NSString*) username_ password: (NSString*) password_ domain: (NSString*) domain_ delegate: (id) delegate_ envelope: (NSString*) envelope_ action: (NSString*) action {
	if (self = [super init]) {
		self.domain = domain_;
		self.url = url_;
		self.username = username_;
		self.password = password_;
		self.delegate = delegate_;
		self.envelope = envelope_;
	}
	return self;
}
- (void)requestFinished:(ASIHTTPRequest *)request_ {
	NSString* responseString = [request_ responseString];
	responseString = [responseString stringByReplacingOccurrencesOfString:@"soap:" withString:@""];
	responseString = [responseString stringByReplacingOccurrencesOfString:@"xmlns=\"http://schemas.microsoft.com/sharepoint/soap/\"" withString:@""];
	CXMLDocument* doc = [[CXMLDocument alloc] initWithData: [responseString dataUsingEncoding:NSUTF8StringEncoding] options: 0 error: nil];
	[delegate requestFinishedWithXml:doc];
	[doc release];
}
- (void)requestFailed:(ASIHTTPRequest *)request_ {
	[delegate requestFinishedWithError:[request_ error]];
}
- (void) startRequest {
	self.request = [ASIHTTPRequest requestWithURL:url];
	[request setUsername:username];
	[request setPassword:password];
	[request setDomain:domain];
	[request addRequestHeader:@"SOAPAction" value:action];
	[request addRequestHeader:@"Content-Type" value:@"text/xml; charset=\"UTF-8\""];
	[request setPostBody:[NSMutableData dataWithData:[envelope dataUsingEncoding:NSUTF8StringEncoding]]];
	[request addRequestHeader:@"Content-Length" value:[NSString stringWithFormat:@"%i", [envelope length]]];
	[request setDelegate:self];
	[request startAsynchronous];
}
- (void) dealloc {
	[super dealloc];
	[request release];
	[password release];
	[domain release];
	[username release];
	[envelope release];
}

@end
