//
//  editBloodViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 09/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface editBloodViewController : UIViewController <UIPickerViewDelegate, UIPickerViewDataSource> {

	IBOutlet UIPickerView * pickerBlood ;
	NSMutableArray * listBlood ;

	IBOutlet UILabel * typeBloodField ;

}

-(IBAction)saveAction:(id)sender ;
-(NSString *) dataFilePath ;

@end
