#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface WebViewVC : VCBase {
	IBOutlet UIWebView* webView;
	NSString* url;
}

@property (nonatomic, retain) IBOutlet UIWebView* webView;
@property (nonatomic, retain) NSString* url;

@end
