
//Copyright Applicable Ltd 2011

#import <UIKit/UIKit.h>
#import "CommonViewControllerBase.h"
#import <MessageUI/MessageUI.h>

@interface ItemDetailsViewController : CommonViewControllerBase <MFMailComposeViewControllerDelegate> {

	NSDictionary* data;
	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* addrLbl;
	IBOutlet UILabel* addr2Lbl;
	IBOutlet UILabel* cityLbl;
	IBOutlet UILabel* postcodeLbl;
	IBOutlet UILabel* phoneLbl;
	IBOutlet UILabel* webstieLbl;
}

@property (nonatomic, retain) NSDictionary* data;
@property (nonatomic, retain) UILabel* titleLbl;
@property (nonatomic, retain) UILabel* addrLbl;
@property (nonatomic, retain) UILabel* addr2Lbl;
@property (nonatomic, retain) UILabel* cityLbl;
@property (nonatomic, retain) UILabel* postcodeLbl;
@property (nonatomic, retain) UILabel* phoneLbl;
@property (nonatomic, retain) UILabel* websiteLbl;

- (IBAction) mailAction: (id) sender;

@end
