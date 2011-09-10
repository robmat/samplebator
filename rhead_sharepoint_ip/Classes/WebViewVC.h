#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface WebViewVC : VCBase {
	IBOutlet UIWebView* webView;
	NSURL* url;
}

@property (nonatomic, retain) IBOutlet UIWebView* webView;
@property (nonatomic, retain) NSURL* url;

@end
