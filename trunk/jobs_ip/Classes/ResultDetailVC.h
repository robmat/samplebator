#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "ASIHTTPRequestDelegate.h"

@interface ResultDetailVC : VCBase <ASIHTTPRequestDelegate> {
	
	IBOutlet UILabel* jobTitleTxt;
	IBOutlet UILabel* placeTxt;
	IBOutlet UILabel* salaryTxt;
	IBOutlet UIWebView* descriptionTxt;
	NSString* jobId;
}

@property(nonatomic,retain) IBOutlet UILabel* jobTitleTxt;
@property(nonatomic,retain) IBOutlet UILabel* placeTxt;
@property(nonatomic,retain) IBOutlet UILabel* salaryTxt;
@property(nonatomic,retain) IBOutlet UIWebView* descriptionTxt;
@property(nonatomic,retain) NSString* jobId;

- (void)applyAction: (id) sender;

@end
