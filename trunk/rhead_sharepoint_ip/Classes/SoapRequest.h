#import "ASIHTTPRequest.h"
#import <Foundation/Foundation.h>
#import "CXMLDocument.h"
#import "ASIHTTPRequestDelegate.h"

@protocol SoapRequestDelegate <NSObject>
- (void) requestFinishedWithXml: (CXMLDocument*) doc;
- (void) requestFinishedWithError: (NSError*) error;
@end


@interface SoapRequest : NSObject <ASIHTTPRequestDelegate> {
	ASIHTTPRequest *request;
	NSURL* url;
	NSString* username;
	NSString* password;
	NSString* domain;
	id <SoapRequestDelegate> delegate;
	NSString* envelope;
	NSString* action;
}

@property (nonatomic, retain) NSURL* url;
@property (nonatomic, retain) ASIHTTPRequest *request;
@property (nonatomic, retain) NSString* username;
@property (nonatomic, retain) NSString* password;
@property (nonatomic, retain) NSString* domain;
@property (nonatomic, assign) id <SoapRequestDelegate> delegate;
@property (nonatomic, retain) NSString* envelope;
@property (nonatomic, retain) NSString* action;

- (SoapRequest*) initWithUrl: (NSURL*) url username: (NSString*) username password: (NSString*) password domain: (NSString*) domain delegate: (id) delegate envelope: (NSString*) envelope_ action: (NSString*) action;
- (void) startRequest;
- (void)requestFinished:(ASIHTTPRequest *)request;
- (void)requestFailed:(ASIHTTPRequest *)request;
@end
