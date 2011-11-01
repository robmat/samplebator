#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface WebViewVC : VCBase <UIWebViewDelegate> {
	IBOutlet UIWebView* webView;
	NSString* url;
	IBOutlet UIActivityIndicatorView* indicator;
@public
	BOOL dontAppendPass;
}

@property (nonatomic, retain) IBOutlet UIWebView* webView;
@property (nonatomic, retain) NSString* url;
@property (nonatomic, retain) IBOutlet UIActivityIndicatorView* indicator;

@end
